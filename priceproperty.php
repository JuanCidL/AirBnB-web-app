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
                <th border="1" style="background-color: #7063FFDB"> Property name </th>
                <th border="1" style="background-color: #7063FFDB"> Price </th>
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
           $variable1=$_GET['name'];
           $variable2=$_GET['price'];
           $stmt = $pdo->prepare('SELECT p.name AS nombre_propiedad, p.price AS precio
                                  FROM airbnb.property AS p
                                  JOIN airbnb.host AS h ON p.host_id = h.host_id
                                  JOIN airbnb.location AS l ON p.latitude = l.latitude AND p.longitude = l.longitude
                                  WHERE p.price < :precio AND l.city = :ciudad
                                  ORDER BY h.host_since ASC;');
           $stmt->execute(['ciudad' => $variable1, 'precio' => $variable2]);
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
