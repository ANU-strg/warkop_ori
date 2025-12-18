<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Checkout - Warkop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4">
            <div class="flex items-center">
                <a href="<?php echo e(route('cart.index')); ?>" class="text-gray-600 hover:text-gray-800 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Checkout</h1>
                    <p class="text-sm text-gray-600">Table <?php echo e($tableInfo['table_number']); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 py-6">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Summary</h2>
            <div class="space-y-3">
                <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between items-center">
                        <div class="flex-1">
                            <p class="font-medium text-gray-800"><?php echo e($item['name']); ?></p>
                            <p class="text-sm text-gray-600">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?> x <?php echo e($item['quantity']); ?></p>
                        </div>
                        <p class="font-semibold text-gray-800">Rp <?php echo e(number_format($item['price'] * $item['quantity'], 0, ',', '.')); ?></p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <div class="border-t mt-4 pt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-gray-800">Total</span>
                    <span class="text-2xl font-bold text-blue-600">Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                </div>
            </div>
        </div>

        <!-- Payment Button -->
        <form action="<?php echo e(route('checkout.process')); ?>" method="POST" id="checkoutForm">
            <?php echo csrf_field(); ?>
            
            <!-- Payment Method Selection -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Select Payment Method</h2>
                
                <div class="space-y-3">
                    <!-- Online Payment Option -->
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                        <input type="radio" name="payment_method" value="online" class="w-5 h-5 text-blue-600" required checked>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span class="font-semibold text-gray-800">Online Payment</span>
                            </div>
                            <p class="text-sm text-gray-600 ml-8">Pay via GoPay, OVO, Bank Transfer, Credit Card</p>
                        </div>
                    </label>

                    <!-- Cash Payment Option -->
                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 transition">
                        <input type="radio" name="payment_method" value="cash" class="w-5 h-5 text-green-600" required>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="font-semibold text-gray-800">Cash Payment</span>
                            </div>
                            <p class="text-sm text-gray-600 ml-8">Pay at cashier counter</p>
                        </div>
                    </label>
                </div>

                <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-2"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-4 rounded-lg text-lg font-medium hover:bg-blue-700">
                Confirm Order
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-4" id="paymentInfo">
            Select your preferred payment method
        </p>
    </div>

    <script>
        // Update info text based on selected payment method
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const paymentInfo = document.getElementById('paymentInfo');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                if (this.value === 'online') {
                    paymentInfo.textContent = 'You will be redirected to online payment gateway';
                } else {
                    paymentInfo.textContent = 'Please bring your order receipt to the cashier';
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH D:\KULIAH\Semester 7\Proyek Teknologi Informasi\warkop_ori\resources\views/checkout.blade.php ENDPATH**/ ?>