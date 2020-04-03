console.log("helpers.js loading");

// Function that can adjust a link to just be a "#" symbol if you're on that page already.
function adjustLink(link) {
  if (window.location.pathname.substr(-1 * link.length) == link) {
    return "#";
  } else {
    return link;
  }
}
