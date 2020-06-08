<div class="action-form-class">
    @if($form)
        @form($form)
        <input type="hidden" name="formData" wire:model='formData'>
    @endif
</div>
