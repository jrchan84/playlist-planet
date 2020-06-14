<?php

/**
  * Function to query information based on
  * a parameter: in this case, first name.
  *
  */

if (isset($_POST['submit'])) {
    try {
        require "../config.php";
        require "../common.php";

        // make database connection
        $connection = new PDO($dsn, $username, $password, $options);

        // SQL read statement
        $sql = "SELECT artists.name
                FROM artists, performed_by, tracks
                WHERE 
                    tracks.album = :album_name AND
                    tracks.media_id = performed_by.media_id AND
                    performed_by.artist_id = artists.artist_id";
        
        // Store album name variable
        $album_name = $_POST['album_name'];

        // Prepare, bind and execute SQL statement
        $statement = $connection->prepare($sql);
        $statement->bindParam(':album_name', $album_name, PDO::PARAM_STR);
        $statement->execute();

        // Fetch result
        $result = $statement->fetchAll();

    } catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>

<?php require "templates/header.php"; ?>

<?php
if (isset($_POST['submit'])) {
    // Check to see if there is a non-empty set of results
    if ($result && $statement->rowCount() > 0) { ?>
        <h2>Search Results</h2>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Artist(s) Name(s)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $row) { ?>
                <tr>
                    <td><?php echo escape($row["album_name"]); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        No results found for <?php echo escape($_POST['album_name']); ?>.
    <?php }
} ?>

<h2>Find Album Artist</h2>

<form method="post">
    <label for="album_name">Album Name</label>
    <input type="text" id="album_name" name="album_name">

    <input type="submit" name="submit" value="View Results">
</form>

<a href="index.php"> Back to main page</a>

<?php include "templates/footer.php"; ?>