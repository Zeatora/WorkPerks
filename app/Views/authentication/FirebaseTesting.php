<?= $this->extend('layout/templateOtherPages') ?>

<?= $this->section('content') ?>
<div class="container mt-10">
    <div class="row">
        <div class="col pad-5">
        <h1>Welcome to <?= $title ?></h1>
            <section class="intro">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="card bg-dark shadow-2-strong">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-dark table-borderless mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">NO</th>
                                                    <th scope="col">ID</th>
                                                    <th scope="col">USERNAME</th>
                                                    <th scope="col">EMAIL</th>
                                                    <th scope="col">NAME</th>
                                                    <th scope="col">ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                <?php foreach($users as $user): ?>
                                                <tr>
                                                    <th scope="row"><?= $i++ ?></th>
                                                    <td><?= $user['id'] ?></td>
                                                    <td><?= $user['data']['username'] ?></td>
                                                    <td><?= $user['data']['email'] ?></td>
                                                    <td><?= $user['data']['name'] ?></td>
                                                    <td style="margin-right: 5px;"><a href="/pages/dashboard/modify/<?= $user['id'] ?>" class="btn btn-warning">EDIT</a><a href="/pages/dashboard/delete/<?= $user['id'] ?>" class="btn btn-danger">DELETE</a></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="/pages/functions/dashboard_function/create" class="btn btn-success mt-2">Create new entry</a>
                </div>
            </section>
        </div>
    </div>
</div>

<?= $this->endSection() ?>