import { Component, EventEmitter, Input, Output } from '@angular/core';


@Component({
 selector: 'app-confirm-modal',
 templateUrl: './logout.html'
})
export class ConfirmModalComponent {
 @Input() title: string = '¿Estás seguro?';
 @Input() message: string = 'Esta acción no se puede deshacer.';
 @Output() confirm = new EventEmitter<void>();
 @Output() cancel = new EventEmitter<void>();
}