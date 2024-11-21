<?php
   include "../../assets/db/db.php";
   session_start();
   
   // Redireciona se o utilizador tiver sessao iniciada
   if(isset($_SESSION['user_id'])){
       header('Location: ../../index.php');
       exit();
   }
   
   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       $username = $_POST['username'];
       $password = $_POST['password'];
   
           // Consulta SQL para verificar as credenciais do utilizador
           $sql = "SELECT id_utilizador, palavra_passe, adm FROM utilizadores WHERE nome = ?";
           $stmt = $conn->prepare($sql);
           $stmt->bind_param("s", $username);
           $stmt->execute();
           $stmt->store_result();
   
           if ($stmt->num_rows > 0) {
               $stmt->bind_result($user_id, $hashed_password,$admin);
               $stmt->fetch();
   
               // Verifica a palavra passe
               if (password_verify($password, $hashed_password)) {
                   // Credenciais corretas, define a variável de sessão
                   $_SESSION['user_id'] = $user_id;
                   $_SESSION['username'] = $username;
                   $_SESSION['login_time'] = time();
                   $_SESSION['is_admin'] = ($admin == 0) ? false : true;
                   // Redireciona para a página do utilizador
                   header('Location: ../../');
                   exit();
               } else {
                   // Palavra passe incorreta
                   $_SESSION['error'] = "Nome de utilizador ou palavra passe incorretos.";
                   header('Location: login.php');
                   exit();
               }
           } else {
               // Nome de utilizador não encontrado
               $_SESSION['error'] = "O nome de utilizador que inseriu não existe.";
               header('Location: login.php');
               exit();
           }
   
           $stmt->close();
       }
       
       $conn->close();
   ?>
<!DOCTYPE html>
<html lang="pt">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Tugames - Login</title>
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
            <form action="login.php" method="POST" class="mt-4">
               <div class="mb-3">
                  <label for="username" class="form-label">Nome de Utilizador</label>
                  <input type="text" class="form-control" id="username" name="username" placeholder="Digite o seu nome de utilizador" required>
               </div>
               <div class="mb-3 position-relative">
                  <label for="password" class="form-label">Palavra Passe</label>
                  <div class="input-group">
                     <input type="password" class="form-control" id="password" name="password" placeholder="Digite a sua palavra passe" required>
                     <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                     <i class="fas fa-eye"></i>
                     </span>
                  </div>
               </div>
               <div class="d-grid">
                  <button type="submit" class="btn btn-primary">Entrar</button>
               </div>
               <div class="text-center mt-2 fs-6">
                  <a href="../register/register.php" class="btn btn-secondary">Não tem conta? Criar Conta</a>
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
         document.querySelector('#togglePassword').addEventListener('click', function () {
             const passwordField = document.querySelector('#password');
             const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
             passwordField.setAttribute('type', type);
         
             // Alterna o ícone de olho
             this.querySelector('i').classList.toggle('fa-eye');
             this.querySelector('i').classList.toggle('fa-eye-slash');
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