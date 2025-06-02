<style>
    /* Loader covers the main content area, excluding the sidebar */
    #loader {
        position: fixed;
        top: 0;
        left: 60px; /* Match sidebar width */
        width: calc(100% - 60px);
        height: 100%;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        transition: opacity 1s ease-in-out;
    }

    .expanded-sidebar #loader {
        left: 200px;
        width: calc(100% - 200px); /* Expanded sidebar width */
    }

    #loader.hidden {
        opacity: 0;
        pointer-events: none;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #0d6efd;
    }
</style>

<div id="loader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loader = document.getElementById('loader');
        loader.classList.add('hidden');

        document.querySelectorAll('a').forEach(link => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('http') && !href.startsWith('#')) {
                link.addEventListener('click', e => {
                    e.preventDefault();
                    loader.classList.remove('hidden');
                    setTimeout(() => window.location.href = href, 200);
                });
            }
        });
    });
</script>
