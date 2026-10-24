<?php
/** @var \App\Model\Joueur $joueur */
/** @var \App\Model\Partie[] $parties */
?>
<h1 style="text-align:center;">Historique des parties</h1>
<p>Joueur : <b><?= htmlspecialchars((string) $joueur) ?></b></p>

<table border="1" cellpadding="4">
    <thead>
        <tr style="background-color:#e6e6e6;">
            <th width="15%"><b>N°</b></th>
            <th width="35%"><b>Mode</b></th>
            <th width="25%"><b>Tentatives</b></th>
            <th width="25%"><b>Terminée</b></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($parties as $partie): ?>
        <tr>
            <td width="15%"><?= (int) $partie->getId() ?></td>
            <td width="35%"><?= htmlspecialchars($partie->getMode()->name) ?></td>
            <td width="25%"><?= $partie->getNbTentatives() ?></td>
            <td width="25%"><?= $partie->estTerminee() ? 'Oui' : 'Non' ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>