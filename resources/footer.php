
    </div>
    <footer id="footerBar" class="gridcontainer">
      <div class="left verticalcenter" id="logoFooterSegment">
        <div id="logoFooter">
          ThinkQuizzy
        </div>
        <div class="footerText">
          We had &copy;2020 vision.
        </div>
      </div>
      <div class="right verticalcenter">
        <div class="gridcontainer2">
          <span class="footerText footerLink verticalcenter" id="about">About</span>
          <span class="footerText footerLink verticalcenter" id="contact">Contact</span>
          <span class="footerText footerLink verticalcenter" id="stats">Stats</span>
        </div>
      </div>
      <div class="center footerText" id="aboutText" hidden>
        Quiz site project for ITWS 1100 Group 8.
      </div>
      <div class="center footerText" id="contactText" hidden>
        Ha, you really thought we'd give away our emails here? You know who we are.
      </div>
      <div class="center footerText" id="statsText" hidden>
        No quiz views yet. We should add some PHP here eventually to display that info.
      </div>
      <script>
        // Script that lets you select footer buttons and reveal a drop-down info tab
        $(document).ready(function() {
          $("#aboutText").hide(0);
          $("#contactText").hide(0);
          $("#statsText").hide(0);
          $("#about").click(function() {
            $("#aboutText").toggle(0);
            $("#contactText").hide(0);
            $("#statsText").hide(0);
            if($("#about").hasClass("selectedText")) {
              $("#about").removeClass("selectedText");
            } else {
              $("#about").addClass("selectedText");
            }
          });
          $("#contact").click(function() {
            $("#contactText").toggle(0);
            $("#aboutText").hide(0);
            $("#statsText").hide(0);
            if($("#contact").hasClass("selectedText")) {
              $("#contact").removeClass("selectedText");
            } else {
              $("#contact").addClass("selectedText");
            }
          });
          $("#stats").click(function() {
            $("#statsText").toggle(0);
            $("#contactText").hide(0);
            $("#aboutText").hide(0);
            if($("#stats").hasClass("selectedText")) {
              $("#stats").removeClass("selectedText");
            } else {
              $("#stats").addClass("selectedText");
            }
          });
        });
      </script>
    </footer>
  </div>
</body>
