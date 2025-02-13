<script>
    document.onkeydown = function(e) {
        if (e.ctrlKey &&
            (e.keyCode === 67 ||
                e.keyCode === 85 ||
                e.keyCode === 117)) {
            Swal.fire('Oops', 'ctrl+u,c disabled', 'error');
            return false;
        } else {
            return true;
        }
    };
    $(document).keypress("u", function(e) {
        if (e.ctrlKey) {
            Swal.fire('Oops', 'ctrl+u disabled', 'error');
            return false;
        } else {
            return true;
        }
    });

    function clickIE4() {
        if (event.button == 2) {
            alert(message);
            return false;
        }
    }

    function clickNS4(e) {
        if (document.layers || document.getElementById && !document.all) {
            if (e.which == 2 || e.which == 3) {
                alert(message);
                return false;
            }
        }
    }
    if (document.layers) {
        document.captureEvents(Event.MOUSEDOWN);
        document.onmousedown = clickNS4;
    } else if (document.all && !document.getElementById) {
        document.onmousedown = clickIE4;
    }

    document.oncontextmenu = new Function("Swal.fire('Oops','klik kanan disabled','error');return false")
</script>