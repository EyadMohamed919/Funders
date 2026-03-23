<?php 
class AdminView
{
    function showAll($data)
    {
        echo "<table border=1>";
        echo "<tr>
                <th>Subscription ID</th>
                <th>Document URL</th>
                <th>Timestamp</th>
                <th>Decision</th>
              </tr>";

        foreach ($data as $row){
            echo "<tr>";
            echo "<td>".$row['SubscriptionID']."</td>";
            echo "<td>".$row['DocumentURL']."</td>";
            echo "<td>".$row['TimeStamp']."</td>";
            echo "<td>
                    <a href='./approve.php?id=".$row['SubscriptionID']."'>Approve</a>
                    <a href='./reject.php?id=".$row['SubscriptionID']."'>Reject</a>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    }
}
?>