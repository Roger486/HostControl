import { TestBed } from '@angular/core/testing';

import { PerfilTemporalService } from './perfil-temporal.service';

describe('PerfilTemporalService', () => {
  let service: PerfilTemporalService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(PerfilTemporalService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
