<?php

class AdminView
{

    function showAll($obj)
    {
        foreach ($obj as $row){
        echo "<table border=1><tr><td>Subscription ID</td> <td>".$obj->SubscripitionID."</td>";
        echo "</tr>";
        
        echo "<tr><td>Document URL</td> <td>".$obj->DocumentURL."</td>";
        echo "</tr>";
        
        echo "<tr><td>Timestamp</td> <td>".$obj->Timestamp."</td>";
        echo "</tr>";

        echo "<tr><td>Decision</td> <td><a href='./approve.php?id=".$obj->SubscriptionID."' class='btn btn-primary'>Approve</a>
        <a href='./reject.php?id=".$obj->SubscriptionID."' class='btn btn-primary'>Reject</a></td>";
        echo "</tr>";

        echo "</table>";
        }
    }
}
?>