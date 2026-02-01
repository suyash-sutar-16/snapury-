<?php
// payment_gateway.php
// Single-file Razorpay integration (create order + verify + save in DB)
// ----------------- CONFIG -----------------
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'snapury_suyash'; // change to your DB

// Razorpay test keys (replace with your keys)
define('RAZORPAY_KEY_ID', 'rzp_test_yourkeyid');
define('RAZORPAY_KEY_SECRET', 'your_secret_here');

// Start session if you use user auth
session_start();

// Create DB connection (mysqli)
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("DB connect error: " . $conn->connect_error);
}

// Helper: JSON response
function json_resp($arr) {
    header('Content-Type: application/json');
    echo json_encode($arr);
    exit;
}

// ----------------- BACKEND ACTIONS -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create_order') {
        // Expecting amount in rupees (float or int). We'll convert to paise integer.
        $amount_rupees = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
        if ($amount_rupees <= 0) json_resp(['status'=>'error','message'=>'Invalid amount']);

        $amount_paise = intval(round($amount_rupees * 100)); // Razorpay expects integer paise
        $receipt_id = 'rcpt_' . time();

        // Create order via Razorpay Orders API using cURL (no SDK)
        $url = "https://api.razorpay.com/v1/orders";
        $data = json_encode([
            'amount' => $amount_paise,
            'currency' => 'INR',
            'receipt' => $receipt_id,
            'payment_capture' => 1
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            json_resp(['status'=>'error','message'=>'Curl error: '.$err]);
        }
        curl_close($ch);

        $order = json_decode($resp, true);
        if ($httpcode === 200 || $httpcode === 201) {
            // return order to frontend
            json_resp(['status'=>'success','order'=>$order]);
        } else {
            json_resp(['status'=>'error','message'=>'Razorpay error','detail'=>$order]);
        }
    }

    if ($action === 'verify_payment') {
        // Frontend will post: razorpay_payment_id, razorpay_order_id, razorpay_signature, amount
        $payment_id = $_POST['razorpay_payment_id'] ?? '';
        $order_id = $_POST['razorpay_order_id'] ?? '';
        $signature = $_POST['razorpay_signature'] ?? '';
        $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

        if (!$payment_id || !$order_id || !$signature) {
            json_resp(['status'=>'error','message'=>'Missing payment parameters']);
        }

        // Verify signature: HMAC_SHA256(order_id + "|" + payment_id, secret)
        $expected_signature = hash_hmac('sha256', $order_id . '|' . $payment_id, RAZORPAY_KEY_SECRET);

        if (hash_equals($expected_signature, $signature)) {
            // Verification success -> store in DB
            $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;

            $stmt = $conn->prepare("INSERT INTO payments (user_id, order_id, payment_id, amount, status) VALUES (?, ?, ?, ?, ?)");
            $status = 'Success';
            $amount_decimal = number_format($amount, 2, '.', '');
            $stmt->bind_param('issds', $user_id, $order_id, $payment_id, $amount_decimal, $status);
            $ok = $stmt->execute();
            if ($ok) {
                json_resp(['status'=>'success','message'=>'Payment verified and saved']);
            } else {
                json_resp(['status'=>'error','message'=>'DB insert failed: '.$stmt->error]);
            }
        } else {
            // signature mismatch
            // Optional: store failed attempt
            $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : null;
            $stmt = $conn->prepare("INSERT INTO payments (user_id, order_id, payment_id, amount, status) VALUES (?, ?, ?, ?, ?)");
            $status = 'Failed - signature_mismatch';
            $amount_decimal = number_format($amount, 2, '.', '');
            $stmt->bind_param('issds', $user_id, $order_id, $payment_id, $amount_decimal, $status);
            $stmt->execute();
            json_resp(['status'=>'error','message'=>'Signature verification failed']);
        }
    }

    // Unknown action
    json_resp(['status'=>'error','message'=>'Unknown action']);
}

// ----------------- FRONTEND (HTML) -----------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Snapury - Checkout (Razorpay)</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Razorpay Checkout JS will be injected dynamically after order creation -->
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
  <div class="max-w-lg w-full bg-white shadow-lg rounded p-6">
    <h2 class="text-2xl font-semibold text-green-700 mb-4">Checkout — Snapury</h2>

    <!-- Simple order summary / amount field -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700">Amount (₹)</label>
      <input id="amount" type="number" step="0.01" value="500.00" class="mt-1 block w-full border rounded px-3 py-2" />
    </div>

    <div class="text-center">
      <button id="payBtn" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Pay with Razorpay</button>
    </div>

    <div id="msg" class="mt-4 text-center text-sm"></div>
  </div>

<script>
document.getElementById('payBtn').addEventListener('click', function(e){
  e.preventDefault();
  const amount = parseFloat(document.getElementById('amount').value);
  if (!amount || amount <= 0) {
    alert('Enter valid amount');
    return;
  }
  this.disabled = true;
  document.getElementById('msg').innerText = 'Creating order...';

  // Create order on server
  fetch('', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: new URLSearchParams({'action':'create_order','amount': amount})
  }).then(r=>r.json()).then(data=>{
    if (data.status === 'success' && data.order) {
      const order = data.order;
      // Setup Razorpay options
      const options = {
        "key": "<?php echo RAZORPAY_KEY_ID; ?>", // Enter the Key ID
        "amount": order.amount, // in paise
        "currency": order.currency,
        "name": "Snapury Grocery",
        "description": "Order Payment",
        "order_id": order.id,
        "handler": function (response){
          // response contains razorpay_payment_id, razorpay_order_id, razorpay_signature
          document.getElementById('msg').innerText = 'Verifying payment...';
          // Post verification to server
          fetch('', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
              'action':'verify_payment',
              'razorpay_payment_id': response.razorpay_payment_id,
              'razorpay_order_id': response.razorpay_order_id,
              'razorpay_signature': response.razorpay_signature,
              'amount': (amount).toFixed(2)
            })
          }).then(r=>r.json()).then(result=>{
            if (result.status === 'success') {
              document.getElementById('msg').innerText = 'Payment successful! Order confirmed.';
              alert('Payment successful!');
              // Optionally redirect to success page
              // window.location.href = 'order_success.php';
            } else {
              document.getElementById('msg').innerText = 'Verification failed: ' + (result.message || '');
              alert('Payment verification failed: ' + (result.message || ''));
            }
          }).catch(err=>{
            console.error(err);
            alert('Server error during verification.');
          });
        },
        "prefill": {
          "name": "<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>",
          "email": "<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>"
        },
        "theme": {
          "color": "#22c55e"
        }
      };
      // open razorpay checkout
      const rzp = new Razorpay(options);
      rzp.on('payment.failed', function (response){
        alert('Payment failed: ' + response.error.description);
      });
      rzp.open();
      document.getElementById('msg').innerText = '';
      document.getElementById('payBtn').disabled = false;
    } else {
      document.getElementById('msg').innerText = 'Order creation failed: ' + (data.message || JSON.stringify(data));
      document.getElementById('payBtn').disabled = false;
    }
  }).catch(err=>{
    console.error(err);
    document.getElementById('msg').innerText = 'Network error';
    document.getElementById('payBtn').disabled = false;
  });
});
</script>

<!-- Load Razorpay checkout script once on page -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</body>
</html>
