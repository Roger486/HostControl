import { ComponentFixture, TestBed } from '@angular/core/testing';

import { VerificarIdentidadComponent } from './verificar-identidad.component';

describe('VerificarIdentidadComponent', () => {
  let component: VerificarIdentidadComponent;
  let fixture: ComponentFixture<VerificarIdentidadComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [VerificarIdentidadComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(VerificarIdentidadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
