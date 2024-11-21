<?php
   include "../../assets/db/db.php";
   session_start();
   
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       // Recebe os dados do formulário
       $username = $_POST['username'];
       $email = $_POST['email'];
       $password = $_POST['password'];
       $passwordC = $_POST['passwordC'];
   
       // Verifica se as passwords coincidem
       if ($password !== $passwordC) {
           $_SESSION['error'] = "As palavras-passe não coincidem.";
           header('Location: register.php');
           exit();
       }
   
       // Verifica se o nome de utilizador ou email já está em uso
       $sql = "SELECT id_utilizador FROM utilizadores WHERE nome = ? OR email = ?";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("ss", $username, $email);
       $stmt->execute();
       $stmt->store_result();
   
       if ($stmt->num_rows > 0) {
           // Nome de utilizador ou email já em uso
           $_SESSION['error'] = "Nome de utilizador ou email já está em uso.";
           header('Location: register.php');
           exit();
       } else {
           // Insere o novo utilizador na base de dados
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);
           $insert_sql = "INSERT INTO utilizadores (nome, email, palavra_passe, adm) VALUES (?, ?, ?, 0)";
           $insert_stmt = $conn->prepare($insert_sql);
           $insert_stmt->bind_param("sss", $username, $email, $hashed_password);
   
           if ($insert_stmt->execute()) {
               // Registo bem-sucedido, cria a sessão e redireciona
               $_SESSION['user_id'] = $insert_stmt->insert_id;
               $_SESSION['username'] = $username;
               $_SESSION['login_time'] = time();
               $_SESSION['is_admin'] = false;  // Definido como não admin (0)
               
               header('Location: ../../');
               exit();
           } else {
               // Erro ao inserir o utilizador
               $_SESSION['error'] = "Erro ao registrar o utilizador. Por favor, tente novamente.";
               header('Location: register.php');
               exit();
           }
       }
   
       // Fecha as instruções
       $stmt->close();
       $insert_stmt->close();
   }
   $conn->close();
   ?>
<!DOCTYPE html>
<html lang="pt">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Tugames - Registo</title>
      <link rel="icon" href="../../assets/img/tugameslogo-512x512.png">
      <!-- CSS Bootstrap -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome Icons -->
      <link rel="stylesheet" href="../../assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Sweet Alert -->
      <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
      <style>
         body, html {
         height: 100%;
         background: url('../../assets/img/banner-bg.jpg') no-repeat center center;
         background-size: cover;
         color: #ffffff;
         }
         .login-container {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100%;
         flex-direction: column;
         padding: 20px;
         }
         .banner img {
         width: 100%;
         max-width: 400px;
         margin-bottom: 20px;
         }
         .form-label {
         color: #ffffff;
         }
         .form-control {
         background-color: #f0f8ff;
         border: 1px solid #ffffff;
         color: #000000;
         }
         .form-control:focus {
         background-color: #ffffff;
         color: #000000;
         }
         .btn-primary {
         background-color: #007bff; 
         border-color: #0056b3;
         }
         .btn-primary:hover {
         background-color: #0056b3;
         border-color: #004494;
         }
         .position-relative i {
         color: #ffffff;
         }
         .input-group {
         position: relative;
         }
         .input-group-text {
         background-color: #ffffff; 
         border-left: 1px solid #ced4da; 
         }
         .input-group .fa-eye , .input-group .fa-eye-slash {
         color: #6c757d;
         }
         .input-group-text:hover {
         background-color: #f8f9fa; 
         }
         .input-group .form-control {
         border-right: none; 
         }
      </style>
   </head>
   <body>
      <div class="login-container">
         <!-- Butão Voltar -->
         <a href="../../../" class="btn btn-primary mt-2 ms-2 position-absolute top-0 start-0"> <i class="fas fa-solid fa-arrow-left"></i> </a>
         <!-- Banner -->
         <div class="banner">
            <img src="../../assets/img/tugameslogo-full-res2.png" alt="Banner de Login">
         </div>
         <!-- Formulário de Login -->
         <div class="col-md-4">
            <form action="register.php" method="POST" class="mt-4">
               <div class="mb-3">
                  <label for="username" class="form-label">Nome de Utilizador</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Digite o seu nome de utilizador" required>
               </div>
               <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Digite o seu email" required>
               </div>
               <div class="mb-3 position-relative">
                  <label for="password" class="form-label">Palavra Passe</label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="password" name="password" placeholder="Digite a sua palavra passe" required>
                     <span class="input-group-text togglePassword"  style="cursor: pointer;">
                     <i class="fas fa-eye"></i>
                     </span>
                  </div>
                  <label for="passwordC" class="form-label">Confirmar Palavra Passe</label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="passwordC" name="passwordC" placeholder="Digite novamente a sua palavra passe" required>
                     <span class="input-group-text togglePassword" style="cursor: pointer;">
                     <i class="fas fa-eye"></i>
                     </span>
                  </div>
               </div>
               <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Submeter</button>
               </div>
               <div class="text-center mt-2 fs-6">
                  <a href="../login/login.php" class="btn btn-secondary">Já tem conta? Faça o Login</a>
               </div>
            </form>
         </div>
      </div>
      <!-- jQuery -->
      <script src="../../assets/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap -->
      <script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- Sweet Alert -->
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.all.min.js"></script>
      <!-- Custom Js -->
      <script>
         document.querySelectorAll('.togglePassword').forEach(function(toggle) {
             toggle.addEventListener('click', function () {
                 const passwordField = document.querySelector('#password');
                 const passwordFieldConfirm = document.querySelector('#passwordC');
         
                 // Verifica qual campo está associado ao ícone clicado
                 if (this.previousElementSibling === passwordField) {
                     const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                     passwordField.setAttribute('type', type);
         
                 } else if (this.previousElementSibling === passwordFieldConfirm) {
                     const type = passwordFieldConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
                     passwordFieldConfirm.setAttribute('type', type);
                 }
                 this.querySelector('i').classList.toggle('fa-eye');
                 this.querySelector('i').classList.toggle('fa-eye-slash');
             });
         });
                  
         // Alerta o utilizador caso necessário utilizando o sweet alert
         document.addEventListener('DOMContentLoaded', function() {
            <?php
            if (isset($_SESSION['error'])) {
                echo "Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: '{$_SESSION['error']}'
                });";
                unset($_SESSION['error']);
            }
            ?>
         });
      </script>
   </body>
</html>