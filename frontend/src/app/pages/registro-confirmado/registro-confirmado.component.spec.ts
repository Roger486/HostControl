import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegistroConfirmadoComponent } from './registro-confirmado.component';

describe('RegistroConfirmadoComponent', () => {
  let component: RegistroConfirmadoComponent;
  let fixture: ComponentFixture<RegistroConfirmadoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegistroConfirmadoComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(RegistroConfirmadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
