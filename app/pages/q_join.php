<?php require('../components/head.php') ?>
        <div class="p-4">
            <div class="card">
                <div class="card-header">
                    Join Query
                </div>
                <div class="card-body">
                    <h5 class="card-title">Drivers of a transit line</h5>
                    <p class="card-text">
                        Select a transit line to see the name and email of the drivers operating in that line.
                    </p>
                    <form method="GET" action="q_join.php">
                        <div class="mb-3 input-group">
                            <label for="line_selection" class="input-group-text">Line</label>
                            <select class="form-select" id="line_selection" name="selection">
                                <option selected>Select...</option>
                                <?php
                                    if (connectToDB()) {
                
                                        // Get line names
                                        $query = "SELECT linename FROM line";
                                        $result = executePlainSQL($query);
                                    
                                        // Generate the options
                                        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                            echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                                        }
                                    
                                        disconnectFromDB();
                                    }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                        
                    </form>
                </div>
            </div>
            <br />
            <div class="card">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Driver Name</th>
                            <th scope="col">Driver Email</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        if (isset($_GET['selection']) && array_key_exists('submit', $_GET) && connectToDB()) {
                            $selected_line = $_GET['selection'];

                            // Query the database
                            $query = "SELECT EMPLOYEE.NAME, EMPLOYEE.EMAIL
                                FROM OPERATES_IN, DRIVES, DRIVER, EMPLOYEE
                                WHERE OPERATES_IN.LINENAME = '$selected_line'
                                AND OPERATES_IN.VEHICLEID = DRIVES.VEHICLEID
                                AND OPERATES_IN.LINENAME = DRIVES.LINENAME
                                AND DRIVES.EMPLOYEEID = DRIVER.EMPLOYEEID
                                AND DRIVER.EMPLOYEEID = EMPLOYEE.EMPLOYEEID";
                            $result = executePlainSQL($query);

                            // Fill out the table
                            $count = 0;
                            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                                $count++;
                                echo "
                                    <tr>
                                    <th scope='row'> $count </th>
                                    <td>" . $row["NAME"] . "</td>
                                    <td>" . $row["EMAIL"] . "</td>
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
