@props([
    'id' => 'confirm-modal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
])

<x-modal :name="$id" maxWidth="md" focusable>
    <div class="px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
        <p class="mt-2 text-sm text-gray-600">{{ $message }}</p>
    </div>

    <div class="flex items-center justify-end gap-3 border-t border-gray-200 px-6 py-4 bg-gray-50">
        <button
            x-on:click="$dispatch('close-modal', '{{ $id }}')"
            type="button"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500"
        >
            {{ $cancelText }}
        </button>
        <button
            type="button"
            id="{{ $id }}-confirm"
            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
            {{ $confirmText }}
        </button>
    </div>
</x-modal>
