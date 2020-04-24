<nav class="navbar navbar-expand-lg navbar-dark bg-dark"> 
  <div class="container">
      <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN') ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="app-nav">
    <ul class="navbar-nav">
        
      <li class="nav-item"> <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES')?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="items.php"><?php echo lang('ITEMS') ?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="members.php"><?php echo lang('MEMBERS') ?></a> </li>
      <li class="nav-item"> <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS') ?></a> </li>
      
      <li class="nav-items dropdown" style="padding-left:40.5rem">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Riyad
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink" style="margin-left:35.5rem">
          <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID'] ?>">Edit Profile</a>
          <a class="dropdown-item" href="#">Settings</a>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </div>
    </div>
</nav>




