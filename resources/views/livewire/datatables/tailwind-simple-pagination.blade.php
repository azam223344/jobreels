<div class="d-flex justify-content-between">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
    <button class="btn btn-primary disabled" disabled>&laquo; Previous</button>
    @else
    <button wire:click="previousPage" class="btn btn-primary">&laquo; Previous</button>
    @endif

    <div style="width: 10px;"></div> <!-- Add a 10px gap between buttons -->

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
    <button wire:click="nextPage" class="btn btn-secondary">Next &raquo;</button>
    @else
    <button class="btn btn-secondary disabled" disabled>Next &raquo;</button>
    @endif
</div>
