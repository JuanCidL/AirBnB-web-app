<!DOCTYPE html>
<html>
    <head>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    </head>
<body>
    <?php
	echo '<div style="display: flex; justify-content: center; align-items: center;">';


        echo '<table border="1" cellpadding="5">';
        echo '<tr>
                <th border="1" style="background-color: #7063FFDB"> Average price </th>
              </tr>';

        class TableRows extends RecursiveIteratorIterator {
            function __construct($it) {
                parent::__construct($it, self::LEAVES_ONLY);
            }
            function current() {
                return '<td border="1" style="background-color: #7063FF68">' . parent::current(). "</td>";
            }
            function beginChildren() {
                echo "<tr>";
            }
            function endChildren() {
                echo "</tr>" . "\n";
            }
        }

        try {
           $pdo = new PDO('pgsql:
                           host=localhost;
                           port=5432;
                           dbname=cc3201;
                           user=webuser;
                           password=12344321');
           $variable1=$_GET['neighbourhood'];
           $stmt = $pdo->prepare('SELECT ROUND(AVG(p.price), 2) AS precio_promedio
                                  FROM airbnb.property AS p
                                  JOIN airbnb.location AS l ON p.latitude = l.latitude AND p.longitude = l.longitude
                                  WHERE l.neighbourhood = :vecindario');
           $stmt->execute(['vecindario' => $variable1]);
           $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

           foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
               echo $v;
           }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
        echo "</table>";
	echo '</div>';

    ?>
</body>
</html>
