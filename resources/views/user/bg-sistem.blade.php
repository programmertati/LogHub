<script>
    var pattern = "{{ $board->pattern }}";

    if (pattern === "system") {
        document.getElementById("bgGrad").classList.add("system");
        document.getElementById("bgGrad").classList.add("no-after");
    }
</script>