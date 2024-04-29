<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_monsters";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

function test_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_monster'])) {
    $monster_id = test_input($_POST["monster_id"]);

    $sql = "DELETE FROM tb_monsters WHERE mon_id=$monster_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Monstro excluído com sucesso!";
    } else {
        echo "Erro ao excluir o monstro: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_monstro'])) {
    $name = test_input($_POST["name"]);
    $description = test_input($_POST["description"]);
    $element_id = test_input($_POST["element"]);

    $sql = "INSERT INTO tb_monsters (mon_name, mon_description, mon_ele_id) VALUES ('$name', '$description', $element_id)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Novo monstro adicionado com sucesso!";
    } else {
        echo "Erro ao adicionar o monstro: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_monster'])) {
    $monster_id = test_input($_POST["monster_id"]);
    $name = test_input($_POST["name"]);
    $description = test_input($_POST["description"]);
    $element_id = test_input($_POST["element"]);

    $sql = "UPDATE tb_monsters SET mon_name='$name', mon_description='$description', mon_ele_id=$element_id WHERE mon_id=$monster_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Monstro atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar o monstro: " . $conn->error;
    }
}

$update_monster_id = "";
$update_monster_name = "";
$update_monster_description = "";
$update_monster_element = "";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $update_monster_id = test_input($_GET['id']);

    $sql = "SELECT mon_name, mon_description, mon_ele_id FROM tb_monsters WHERE mon_id = $update_monster_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $update_monster_name = $row["mon_name"];
        $update_monster_description = $row["mon_description"];
        $update_monster_element = $row["mon_ele_id"];
    } else {
        echo "Nenhum monstro encontrado com o ID fornecido.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAEMONSTERS</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%2210 0 100 100%22><text y=%22.90em%22 font-size=%2290%22>👾</text></svg>"></link>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="css/style.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>
<body>
    <article class="container ">
        
    <div class="text-center my-3">
        <h1>DAEMONSTERS</h1>
    </div>
        
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-primary alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <hr>

    <div class="row d-flex ">
        <div class="box col-lg-5  mx-auto h-25">
            <h2>Novo Daemonster</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if ($update_monster_id != ""): ?>
                <input type="hidden" name="monster_id" value="<?php echo $update_monster_id; ?>">
                <div class="mb-3">
                    <label  for="name">Nome:</label  class="form-label">
                    <input class="form-control" type="text" id="name" name="name" value="<?php echo $update_monster_name; ?>" required>
                </div>
    
                <div class="mb-3">
                    <label for="description" class="form-label">Descrição:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required ><?php echo $update_monster_description; ?></textarea>
                </div>
    
                <label for="element" class="form-label">Elemento:</label>
                <select class="form-select" id="element" name="element" required>
                    <?php
                    $sql = "SELECT * FROM tb_elements";
                    $result = $conn->query($sql);
    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = ($row["ele_id"] == $update_monster_element) ? "selected" : "";
                            echo "<option value='" . $row["ele_id"] . "' $selected>" . $row["ele_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
    
                <input class="btn btn-success my-3" type="submit" name="update_monster" value="Atualizar Monstro">
            <?php else: ?>
                <div class="mb-3">
                    <label for="name" class="form-label">Nome:</label>
                    <input class="form-control" type="text" id="name" name="name" required>
                </div>
    
                <div class="mb-3">
                    <label for="description" class="form-label">Descrição:</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                </div>
    
                <label for="element" class="form-label">Elemento:</label>
                <select class="form-select" id="element" name="element" required>
                    <option value="">Selecione o elemento</option>
                    <?php
                    $sql = "SELECT * FROM tb_elements";
                    $result = $conn->query($sql);
    
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["ele_id"] . "'>" . $row["ele_name"] . "</option>";
                        }
                    }
                    ?>
                </select>
    
                <input class="btn btn-success my-3" type="submit" name="add_monstro" value="Adicionar Monstro">
                
            <?php endif; ?>
            <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary">Cancelar</a>
        </form>
        </div>

        <div class="box col-lg-6 mx-auto  ">
        <h2>Daemonsters</h2>
        <div class="table-responsive"></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Elemento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="table-group-divider">

            <?php
            $sql = "SELECT mon_name, mon_description, ele_name, mon_id
                    FROM tb_monsters 
                    INNER JOIN tb_elements ON tb_monsters.mon_ele_id = tb_elements.ele_id";
            $result = $conn->query($sql);
    
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["mon_name"] . "</td>";
                    echo "<td>" . $row["mon_description"] . "</td>";
                    echo "<td>" . $row["ele_name"] . "</td>";
                    echo "<td>";
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                    echo "<input  type='hidden' name='monster_id' value='" . $row["mon_id"] . "'>";
                    echo "<input class='btn btn-primary btn-sm m-1' type='button' onclick=\"location.href='$_SERVER[PHP_SELF]?id=" . $row["mon_id"] . "'\" value='Editar'>";
                    echo "<input class='btn btn-danger btn-sm m-1' type='submit' name='delete_monster' value='Excluir'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Nenhum monstro encontrado.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
    </div>
    
    <hr>

    <?php
    $conn->close();
    ?>
    </article>
</body>
</html>
