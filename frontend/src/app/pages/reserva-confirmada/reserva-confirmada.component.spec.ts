import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ReservaConfirmadaComponent } from './reserva-confirmada.component';

describe('ReservaConfirmadaComponent', () => {
  let component: ReservaConfirmadaComponent;
  let fixture: ComponentFixture<ReservaConfirmadaComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [ReservaConfirmadaComponent]
    })
    .compileComponents();

    fixture = TestBed.createComponent(ReservaConfirmadaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
