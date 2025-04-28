import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminInmueblesComponent } from './admin-inmuebles.component';

describe('AdminInmueblesComponent', () => {
  let component: AdminInmueblesComponent;
  let fixture: ComponentFixture<AdminInmueblesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [AdminInmueblesComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(AdminInmueblesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
