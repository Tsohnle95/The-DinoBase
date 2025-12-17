                        </div>
                </section>
        </main>
        <footer class="text-bg-dark text-center p-5 mt-5">
                <h2 class="fw-light fs-5">This website is a project contributed by Ty Sohnle, Ethan De Vera, Long Hoang Tran, and Rodney Russell.</h2>
                <p class="m-0">This website is an academic exercise and a portfolio project for four members. Anyone who is not the author of this project, please do not reupload for any purpose.</p>
        </footer>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
        <script>
                // This enables Bootstrap tooltips, which we use to display city trivia. 
                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>
    </body>
</html>

<?php

// Closing our connection.
db_disconnect($connection);

?>