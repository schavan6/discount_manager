function addToCart(product) {
  /*if (localStorage.getItem('cart') == null) {
    localStorage.setItem('cart', []);
  }

  const cart = localStorage.getItem('cart');*/

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

function trial() {
  alert('trial');
  console.log(cart);
}
