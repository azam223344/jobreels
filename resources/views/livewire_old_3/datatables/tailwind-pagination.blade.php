<div class="flex items-center justify-center space-x-2">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
    <button class="pagination-button disabled">
        <span>&laquo; Previous</span>
    </button>
    @else
    <button wire:click="previousPage"
        id="pagination-desktop-page-previous"
        class="pagination-button"
    >
        <span>&laquo; Previous</span>
    </button>
    @endif

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
    <button wire:click="nextPage"
        id="pagination-desktop-page-next"
        class="pagination-button"
    >
        <span>Next &raquo;</span>
    </button>
    @else
    <button
        class="pagination-button disabled"
    >
        <span>Next &raquo;</span>
    </button>
    @endif
</div>
