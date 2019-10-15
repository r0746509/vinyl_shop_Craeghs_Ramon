@extends('layouts.template')

@section('title', 'Welcome to The Vinyl Shop')

@section('main')
<h1>Records</h1>

<ul>
    <?php
    foreach ($records as $record){
        echo "<li> $record </li>";
        //echo '<li>' . $record . '</li>';
    }
    ?>
</ul>
@endsection
