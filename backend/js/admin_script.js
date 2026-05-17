const navbar = document.querySelector('.header .navbar');
const accountBox = document.querySelector('.header .account-box');

document.getElementById('menu-btn')?.addEventListener('click', () => {
   navbar.classList.toggle('active');
   accountBox.classList.remove('active');
});

document.getElementById('user-btn')?.addEventListener('click', () => {
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
});

window.addEventListener('scroll', () => {
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
});

const closeUpdateButton = document.getElementById('close-update');
if (closeUpdateButton) {
   closeUpdateButton.addEventListener('click', () => {
      document.querySelector('.edit-product-form').style.display = 'none';
      window.location.href = 'admin_products.php';
   });
}