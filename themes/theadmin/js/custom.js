// transparant topbar
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
    document.getElementById("topbar").classList.add("topbar-trans");
  } else {
    document.getElementById("topbar").classList.remove("topbar-trans");
  }
}