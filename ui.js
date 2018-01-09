$(document).ready(function() {

    $("#help-icon").click(
        function(event) {
            if ($(this).html() == "?") {
                $("#information").show();
                $("#help-icon").html("X");
            } else {
                $("#information").hide();
                $("#help-icon").html("?");
            }
        }
    );

    $("#clear").click(
        function(event) {
            event.preventDefault();
            window.open("index.php", "_self");
        }
    );

    $("rect").click(
        function(event) {
            $this = $("#"+event.target.id);
            var real = $this.attr("data-r");
            var imaginary = $this.attr("data-i");
            var bsize = $this.attr("data-b");
            window.open("?real="+real+"&imaginary="+imaginary+"&bsize="+bsize, "_self");
        }
    ).mouseover(
        function(event) {
            $("#"+event.target.id).css("opacity", ".5");
        }
    ).mouseout(
        function(event) {
            $("#"+event.target.id).css("opacity", "1");
        }
    );
});