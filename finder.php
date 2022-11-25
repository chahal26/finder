<?php
    Class Finder{
        public array $results = [];
        public string $stringToSearch;
        public $directory;

        public function __construct($stringToSearch, $directory = null){
            $this->stringToSearch = $stringToSearch;
            $this->directory = $directory ?? __dir__;
        }

        public function recursiveScan($dir = null){
            $dir = $dir ?? $this->directory;
            $tree = glob(rtrim($dir, '/') . '/*');
            if (is_array($tree)) {
                foreach($tree as $file) {
                    if (is_dir($file)) {
                        $this->recursiveScan($file);
                    }elseif (is_file($file)) {
                        if( strpos(file_get_contents($file), $this->stringToSearch) !== false) {  
                            $this->results[$file] = nl2br(file_get_contents($file));
                        }
                    }
                }  
            }
        }
    }

    $isSearched = false;
    if(isset($_GET['search']) && !empty($_GET['search'])){
        $dir = __dir__;
        $searchString = $_GET['search'];
        $directory = $_GET['directory'] == '' ? null : $_GET['directory'];
        $directory = $directory ?? null;

        $finder = new Finder($searchString, $directory);
        $finder->recursiveScan();
        $allResults = $finder->results;
        $isSearched = true;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find a string on server</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>
    <header>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Finder</a>
            </div>
        </nav>
    </header>
    <main class="container-fluid mt-4">
        <p class="text-danger">FYI: Leave directory name as empty if you want to search in all folders.</p>
        <div class="card">
            <div class="card-body">
                <form method="get">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" name="search" id="search" class="form-control" placeholder="Enter string to search" value="<?=$_GET['search'] ?? ''?>" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="directory" id="directory" class="form-control" placeholder="Directory Name" value="<?=$_GET['directory'] ?? ''?>" />
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-dark">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($isSearched){ ?>
           <div class="mt-4 mb-4">
                <h4>Search Results (<?=count($allResults)?> files found)</h4><hr/>
                <?php foreach($allResults as $file => $content){ ?>
                    <div class="card mt-2 p-2">
                        <details>
                            <summary><?=$file?></summary>
                            <p><pre><?=$content?></pre></p>
                        </details>
                    </div>
                <?php } ?>
           </div>
        <?php } ?>
    </main>
    <footer class="text-center text-muted" style="position: fixed; bottom: 0; width: 100%;">
        <p class="">Made by <a href="https://github.com/chahal26">Sahil Chahal</a></p>
    </footer>
</body>
</html>