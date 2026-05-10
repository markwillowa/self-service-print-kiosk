<script>
    document.addEventListener('contextmenu', (event) => {
        event.preventDefault();
    });

    document.addEventListener('dragstart', (event) => {
        event.preventDefault();
    });

    document.addEventListener('drop', (event) => {
        event.preventDefault();
    });

    document.addEventListener('keydown', (event) => {
        const blocked = [
            'F1',
            'F2',
            'F3',
            'F4',
            'F5',
            'F6',
            'F7',
            'F8',
            'F9',
            'F10',
            'F11',
            'F12',
        ];

        if (blocked.includes(event.key)) {
            event.preventDefault();
        }

        if (
            event.ctrlKey &&
            [
                'u',
                's',
                'p',
                'o',
                'n',
                't',
                'w',
                'r',
                'j',
            ].includes(event.key.toLowerCase())
        ) {
            event.preventDefault();
        }

        if (
            event.ctrlKey &&
            event.shiftKey &&
            ['i', 'j', 'c'].includes(event.key.toLowerCase())
        ) {
            event.preventDefault();
        }

        if (
            event.altKey &&
            event.key === 'Tab'
        ) {
            event.preventDefault();
        }
    });
</script>
