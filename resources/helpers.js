console.log("helpers.js loading");
function adjustLink(link) {
  if (window.location.pathname.substr(-1 * link.length) == link) {
    return "#";
  } else {
    return link;
  }
}
