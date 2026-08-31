import { Injectable } from '@angular/core';

import { HttpClient } from '@angular/common/http';

import { Observable } from 'rxjs';

import { environment } from '../../environments/environments';

@Injectable({

  providedIn: 'root',

})

export class EventsFormsService {

  private apiUrl = `${environment.endpointUrl}/contact`;

  constructor(private http: HttpClient) {}

  sendEventForm(data: any): Observable<any> {

    return this.http.post(this.apiUrl, data);

  }

}