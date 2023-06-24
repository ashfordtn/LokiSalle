const baseUrl = "http://localhost/lokisalle";
function addToCart(id) {
  $.ajax({
    url: baseUrl + "/pages/manage_panier.php",
    type: "POST",
    data: { productId: id },
    success: function (response) {
      if (response.success) {
        alert(response.message);
        window.location.reload();
      } else {
        alert(response.message);
      }
    },
  });
}
function viderPanier() {
  if (confirm("Voulez-vous vraiment vider votre panier ?")) {
    $.ajax({
      url: baseUrl + "/pages/manage_panier.php",
      type: "GET",
      data: { action: "vider" },
      success: function (response) {
        if (response.success) {
          alert(response.message);
          window.location.reload();
        } else {
          alert(response.message);
        }
      },
    });
  }
}

function removeFromCart(id) {
  $.ajax({
    url: baseUrl + "/pages/manage_panier.php",
    type: "GET",
    data: { id: id, action: "delete" },
    success: function (response) {
      if (response.success) {
        alert("Item successfully removed from the cart.");
        window.location.reload();
      } else {
        alert("Sorry, couldn't remove the item");
      }
    },
    error: function (xhr, status, error) {
      alert("An error occurred while removing the item from the cart.");
    },
  });
}
