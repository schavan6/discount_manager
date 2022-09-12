let productIdToOriginalPrice = new Map();
let productIdToDiscountedPrice = new Map();
let total = 0;
let discountIdToDiscount = new Map();

//add product to cart
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

//checkout cart
function checkout(product) {
  window.location.href = 'checkout.php';
}

function applyDiscount(element) {
  var discountInfo = element.value.split('_');

  //product on which the discount is applied
  var productId = discountInfo[0];
  //id of applied discount
  var discountId = discountInfo[1];

  var discount = discountIdToDiscount.get(discountId);
  var price = productIdToDiscountedPrice.get(productId);

  var priceBeforeDiscount = price;

  var originalPrice = productIdToOriginalPrice.get(productId);

  //discount is applied by checking the checkbox
  if (element.checked) {
    //percentage discount
    if (discount['isPercentage'] === '1') {
      var percent = parseInt(discount['value']);
      var off = (originalPrice * percent) / 100;
    } else {
      //discount in dollars
      var off = parseInt(discount['value']);
    }

    //to make sure discounted price doesn't go below 0
    if (off > price) {
      off = price;
    }

    price -= off;

    productIdToDiscountedPrice.set(productId, price);

    //total price of all cart items
    total -= priceBeforeDiscount - price;
  } else {
    //discount is removed
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

/*
  Make initial maps that will be later used for discount calculation
 */
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
