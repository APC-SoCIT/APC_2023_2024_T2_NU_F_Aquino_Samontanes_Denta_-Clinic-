<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <!-- place navbar here -->
        </header>
        <main>

            <div class="table-container">
                <h2>Appointments</h2>
                <button>Schedule Appointments</button>
                <table>
                    <tr>
                        <td>
                            <h5>Appointment</h5>
                            <p>Atlas Room</p>
                        </td>
                        <td>
                            <div class="column-item">
                                <button value="details">Details</button>
                                <select name="action" id="action_dpdown">
                                    <option value="" selected disabled>Action</option>
                                    <option value="edit_app">Edit Appointment</option>
                                    <option value="cancel_app">Cancel Appointment</option>
                                </select><br>
                                <p>Date</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Another appointment at Atlas Room</h5>
                            <p>Atlas Room</p>
                        </td>
                        <td>
                            <div class="column-item">
                                <button value="details">Details</button>
                                <select name="action" id="action_dpdown">
                                    <option value="" selected disabled>Action</option>
                                    <option value="edit_app">Edit Appointment</option>
                                    <option value="cancel_app">Cancel Appointment</option>
                                </select><br>
                                <p>Date</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5>Appointment at Aeon Room</h5>
                            <p>Atlas Room</p>
                        </td>
                        <td>
                            <div class="column-item">
                                <button value="details">Details</button>
                                <select name="action" id="action_dpdown">
                                    <option value="" selected disabled>Action</option>
                                    <option value="edit_app">Edit Appointment</option>
                                    <option value="cancel_app">Cancel Appointment</option>
                                </select><br>
                                <p>Date</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            
        </main>
        <footer>
            <!-- place footer here -->
        </footer>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
