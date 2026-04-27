<style>
    :root {
        --vm-edit-item-carrito-primary-blue: #3b82f6;
        --vm-edit-item-carrito-primary-hover: #2563eb;
        --vm-edit-item-carrito-border-color: #e5e7eb;
        --vm-edit-item-carrito-text-gray: #374151;
        --vm-edit-item-carrito-background-gray: #f9fafb;
    }

    .vm_edit_item_carrito_modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        border-radius: 8px;
        width: 90%;
        max-width: 700px;
        z-index: 1001;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .vm_edit_item_carrito_modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-bottom: 1px solid var(--vm-edit-item-carrito-border-color);
    }

    .vm_edit_item_carrito_modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--vm-edit-item-carrito-text-gray);
        margin: 0;
    }

    .vm_edit_item_carrito_close-button {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #666;
        padding: 0;
        margin-left: 16px;
    }

    .vm_edit_item_carrito_modal-body {
        padding: 24px;
    }

    .vm_edit_item_carrito_form-group {
        margin-bottom: 20px;
    }

    .vm_edit_item_carrito_form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: var(--vm-edit-item-carrito-text-gray);
        font-size: 1.21rem;
    }

    .vm_edit_item_carrito_form-control {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid var(--vm-edit-item-carrito-border-color);
        border-radius: 6px;
        font-size: 1.21rem;
        transition: border-color 0.15s ease-in-out;
    }

    .vm_edit_item_carrito_form-control:focus {
        outline: none;
        border-color: var(--vm-edit-item-carrito-primary-blue);
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .vm_edit_item_carrito_image-upload {
        border: 2px dashed var(--vm-edit-item-carrito-border-color);
        border-radius: 6px;
        padding: 32px;
        text-align: center;
        background-color: var(--vm-edit-item-carrito-background-gray);
        cursor: pointer;
        transition: border-color 0.15s ease-in-out;
    }

    .vm_edit_item_carrito_image-upload:hover {
        border-color: var(--vm-edit-item-carrito-primary-blue);
    }

    .vm_edit_item_carrito_grid {
        display: grid;
        gap: 20px;
    }

    .vm_edit_item_carrito_grid-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .vm_edit_item_carrito_grid-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    .vm_edit_item_carrito_btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 1.21rem;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.15s ease-in-out;
    }

    .vm_edit_item_carrito_btn-primary {
        background-color: var(--vm-edit-item-carrito-primary-blue);
        color: white;
    }

    .vm_edit_item_carrito_btn-primary:hover {
        background-color: var(--vm-edit-item-carrito-primary-hover);
    }

    .vm_edit_item_carrito_btn-outline {
        background-color: white;
        border-color: var(--vm-edit-item-carrito-border-color);
        color: var(--vm-edit-item-carrito-text-gray);
    }

    .vm_edit_item_carrito_btn-outline:hover {
        background-color: var(--vm-edit-item-carrito-background-gray);
    }

    .vm_edit_item_carrito_btn-group {
        display: flex;
        gap: 8px;
    }

    .vm_edit_item_carrito_btn-group .vm_edit_item_carrito_btn {
        flex: 1;
    }

    .vm_edit_item_carrito_modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 16px 24px;
        border-top: 1px solid var(--vm-edit-item-carrito-border-color);
        background-color: var(--vm-edit-item-carrito-background-gray);
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .vm_edit_item_carrito_form-control.vm_edit_item_carrito_select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    .vm_edit_item_carrito_form-control.vm_edit_item_carrito_readonly {
        background-color: var(--vm-edit-item-carrito-background-gray);
        cursor: not-allowed;
    }

    .vm_edit_item_carrito_icbper-btn {
        padding: 6px 16px;
    }

    .vm_edit_item_carrito_icbper-btn.vm_edit_item_carrito_active {
        background-color: var(--vm-edit-item-carrito-primary-blue);
        color: white;
        border-color: var(--vm-edit-item-carrito-primary-blue);
    }

    .vm_edit_item_carrito_form-control.vm_edit_item_carrito_textarea {
        min-height: 100px;
        resize: vertical;
    }
</style>






<style>
.vm_editar_item_carrito_modal_overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.vm_editar_item_carrito_modal_container {
  background: white;
  border-radius: 12px;
  width: 95%;
  max-width: 600px;
  padding: 1.5rem;
  position: relative;
  max-height: 90vh;
  overflow-y: auto;
}

.vm_editar_item_carrito_modal_header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.vm_editar_item_carrito_modal_title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1a1a1a;
}

.vm_editar_item_carrito_modal_close {
  background: none;
  border: none;
  color: #666;
  cursor: pointer;
  font-size: 1.5rem;
  padding: 0.5rem;
}

.vm_editar_item_carrito_modal_top_section {
  display: grid;
  grid-template-columns: 200px 1fr;
  gap: 1.5rem;
  margin-bottom: 1rem;
}

.vm_editar_item_carrito_modal_image_container {
  width: 200px;
  height: 200px;
  position: relative;
  overflow: hidden;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.vm_editar_item_carrito_modal_image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  position: absolute;
  top: 0;
  left: 0;
}

.vm_editar_item_carrito_modal_form_group {
  margin-bottom: 1rem;
}

.vm_editar_item_carrito_modal_label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #4a5568;
}

.vm_editar_item_carrito_modal_select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 13px;
  background-color: white;
}

.vm_editar_item_carrito_modal_textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 13px;
  min-height: 80px;
  resize: vertical;
}

.vm_editar_item_carrito_modal_input_group {
  position: relative;
  display: flex;
  align-items: center;
}

.vm_editar_item_carrito_modal_input {
  width: 100%;
  padding: 0.75rem;
  padding-left: 30px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 13px;
}

.vm_editar_item_carrito_modal_input:disabled {
  background-color: #f8f9fa;
  color: #4a5568;
}

.vm_editar_item_carrito_modal_currency {
  position: absolute;
  left: 0.75rem;
  color: #4a5568;
}

.vm_editar_item_carrito_modal_quantity_icon {
  position: absolute;
  left: 0.75rem;
  color: #4a5568;
}

.vm_editar_item_carrito_modal_calculations {
  background: #f8fafc;
  padding: 1.25rem;
  border-radius: 8px;
}

.vm_editar_item_carrito_modal_calc_grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
}

.vm_editar_item_carrito_modal_footer {
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  margin-top: 1.5rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
}

.vm_editar_item_carrito_modal_button {
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.vm_editar_item_carrito_modal_button_cancel {
  background: #fff;
  border: 1px solid #e2e8f0;
  color: #4a5568;
}

.vm_editar_item_carrito_modal_button_accept {
  background: #6366f1;
  border: 1px solid #6366f1;
  color: white;
}

@media (max-width: 640px) {
  .vm_editar_item_carrito_modal_top_section {
    grid-template-columns: 100px 1fr;
  }
  
  .vm_editar_item_carrito_modal_image_container {
    width: 100px;
    height: 100px;
  }
  
  .vm_editar_item_carrito_modal_calc_grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .vm_editar_item_carrito_modal_container {
    padding: 1rem;
  }
}
</style>