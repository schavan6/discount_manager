let productIdToOriginalPrice = new Map();
let productIdToDiscountedPrice = new Map();
let total = 0;
let discountIdToDiscount = new Map();

function addToCart(product) {
  var pid = product['id'];
  $.ajax({
    url: 'action.php',
    method: 'POST',
    data: { addToCart: 1, proId: pid },
    success: function (data) {
      alert(data);
    },
  });
}

function checkout(product) {
  window.location.href = 'checkout.php';
}

function applyDiscount(element) {
  var discountInfo = element.value.split('_');

  var productId = discountInfo[0];
  var discountId = discountInfo[1];

  var discount = discountIdToDiscount.get(discountId);
  var price = productIdToDiscountedPrice.get(productId);

  var priceBeforeDiscount = price;

  var originalPrice = productIdToOriginalPrice.get(productId);
  console.log(element.checked);
  if (element.checked) {
    if (discount['isPercentage'] === '1') {
      var percent = parseInt(discount['value']);
      price -= (originalPrice * percent) / 100;
    } else {
      price -= parseInt(discount['value']);
    }

    productIdToDiscountedPrice.set(productId, price);

    total -= priceBeforeDiscount - price;
  } else {
    if (discount['isPercentage'] === '1') {
      var percent = parseInt(discount['value']);
      price += (originalPrice * percent) / 100;
    } else {
      price += parseInt(discount['value']);
    }

    productIdToDiscountedPrice.set(productId, price);

    total += price - priceBeforeDiscount;
  }

  $('#total').html(total);

  $('#p_' + productId).html(price);
}

function saveProductInfo(products, discounts) {
  products.forEach((product) => {
    productIdToOriginalPrice.set(product['id'], parseInt(product['price']));
    productIdToDiscountedPrice.set(product['id'], parseInt(product['price']));
    total += parseInt(product['price']);
  });
  discounts.forEach((discount) => {
    discountIdToDiscount.set(discount['id'], discount);
  });

  $('#total').html(total);
}

function deleteCart(sessionId) {
  $.ajax({
    url: 'action.php',
    method: 'POST',
    data: { logout: 1, userId: sessionId },
  });
}
