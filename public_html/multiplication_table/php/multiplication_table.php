<table>
    <?php
    // Создаем заголовок таблицы (верхняя строка)
    echo "<tr>";
    echo "<td class='header'></td>";
    
    for ($i = 1; $i <= 10; $i++) {
        echo "<td class='header'>$i</td>";
    }
    echo "</tr>";
    
    // Создаем строки таблицы
    for ($i = 1; $i <= 10; $i++) {
        echo "<tr>";
        
        // Создаем заголовок строки (первый столбец)
        echo "<td class='row-header'>$i</td>";
        
        // Заполняем ячейки с произведениями
        for ($j = 1; $j <= 10; $j++) {
            $result = $i * $j;
            $class = ($i === $j) ? "diagonal" : "";
            echo "<td class='$class'>$result</td>";
        }
        
        echo "</tr>";
    }
    ?>
</table>