<?php

/**
 * Custom Pagination Template
 */

$pager->setSurroundCount(2);

// Build query string for pagination links
$queryString = '';
$params = ['search', 'sortBy', 'sortOrder', 'perPage'];
foreach ($params as $param) {
    if (isset($_GET[$param]) && !empty($_GET[$param])) {
        $queryString .= '&' . $param . '=' . urlencode($_GET[$param]);
    }
}
?>

<nav aria-label="Page navigation">
    <ul class="pagination">
        <!-- First Link -->
        <?php if ($pager->hasPreviousPage()): ?>
            <li>
                <a href="<?= $pager->getFirst() . $queryString ?>" aria-label="First">
                    <i class="icofont-double-left"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Previous Link -->
        <?php if ($pager->hasPreviousPage()): ?>
            <li>
                <a href="<?= $pager->getPreviousPage() . $queryString ?>" aria-label="Previous">
                    <i class="icofont-arrow-left"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Page Links -->
        <?php foreach ($pager->links() as $link): ?>
            <li class="<?= $link['active'] ? 'active' : '' ?>">
                <?php if ($link['active']): ?>
                    <span><?= $link['title'] ?></span>
                <?php else: ?>
                    <a href="<?= $link['uri'] . $queryString ?>"><?= $link['title'] ?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>

        <!-- Next Link -->
        <?php if ($pager->hasNextPage()): ?>
            <li>
                <a href="<?= $pager->getNextPage() . $queryString ?>" aria-label="Next">
                    <i class="icofont-arrow-right"></i>
                </a>
            </li>
        <?php endif; ?>

        <!-- Last Link -->
        <?php if ($pager->hasNextPage()): ?>
            <li>
                <a href="<?= $pager->getLast() . $queryString ?>" aria-label="Last">
                    <i class="icofont-double-right"></i>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
