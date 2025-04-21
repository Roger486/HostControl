import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PerfilModificadoComponent } from './perfil-modificado.component';

describe('PerfilModificadoComponent', () => {
  let component: PerfilModificadoComponent;
  let fixture: ComponentFixture<PerfilModificadoComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [PerfilModificadoComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(PerfilModificadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
