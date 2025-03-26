import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConfirmacionReservaComponent } from './confirmacion-reserva.component';

describe('ConfirmacionReservaComponent', () => {
  let component: ConfirmacionReservaComponent;
  let fixture: ComponentFixture<ConfirmacionReservaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ConfirmacionReservaComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ConfirmacionReservaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
