<script>
    let resetTimer;

    function resetKioskTimer() {
        clearTimeout(resetTimer);

        resetTimer = setTimeout(() => {
            window.location.href = "{{ route('kiosk.home') }}";
        }, {{ $seconds * 1000 }});
    }

    ['click', 'touchstart', 'mousemove', 'keydown'].forEach((event) => {
        document.addEventListener(event, resetKioskTimer);
    });

    resetKioskTimer();
</script>
