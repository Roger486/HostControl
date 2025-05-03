import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminClientesComponent } from './admin-clientes.component';

describe('AdminClientesComponent', () => {
  let component: AdminClientesComponent;
  let fixture: ComponentFixture<AdminClientesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminClientesComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminClientesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
