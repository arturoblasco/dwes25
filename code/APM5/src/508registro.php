
<h3>Creación de usuario</h3>
<table>
<form action="508nuevoUsuario.php" method="post">
    <tr>
    <td>
    <label for="nombre">nombre:</label>
    </td>
    <td>
    <input type="text" size="30" required name="nombre">
    </td>
    </tr>

    <tr>
    <td>
    <label for="usuario">usuario:</label>
    </td>
    <td>
    <input type="text" size="30" required name="usuario">
    </td>
    </tr>

    <tr>
    <td>
    <label for="password">password: </label>
    </td>
    <td>
    <input type="password" size="30" required name="password"> 
    </td>
    </tr>

    <tr>
    <td>
    <label for="email">email: </label>
    </td>
    <td>
    <input type="email" size="30" required name="email"> 
    </td>
    </tr>

    <tr>
    <td colspan="2">
    <input type="submit" name="enviar" value="enviar">
    </td>
    </tr>
</form>
</table>