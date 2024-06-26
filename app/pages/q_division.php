<?php require('../components/head.php') ?>
        <div class="p-4">
            <div class="card">
                <div class="card-header">
                    Division Query
                </div>
                <div class="card-body">
                    <h5 class="card-title">Experienced technicians</h5>
                    <p class="card-text">
                        Find the name and salary of the technicians who have serviced
                        all the buses.
                    </p>
                    <form method="GET" action="q_division.php">
                        <button type="submit" class="btn btn-primary" name="submit" >Find</button>
                    </form>
                </div>
            </div>
            <br />
            <div class="card">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Technician Name</th>
                            <th scope="col">Technician Salary</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (array_key_exists('submit', $_GET) && connectToDB()) {

                            // Query the database
                            $query = "SELECT e.name, e.salary
                                FROM employee e, technician t
                                WHERE e.employeeID = t.employeeID
                                AND NOT EXISTS
                                (SELECT b.vehicleID
                                FROM bus b
                                MINUS 
                                (SELECT s.vehicleID
                                FROM services s
                                WHERE s.employeeID = t.employeeID))";
                                
                            $result = executePlainSQL($query);

                            // Fill out the table
                            $count = 0;
                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                $count++;
                                echo "
                                    <tr>
                                    <th scope='row'> $count </th>
                                    <td>" . $row[0] . "</td>
                                    <td>" . $row[1] . "</td>
                                    </tr>";
                            }
                            if ($count == 0) {
                                echo "<tr> <td>No results</td><td></td><td></td> </tr>";
                            }
                            
                            disconnectFromDB();
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
