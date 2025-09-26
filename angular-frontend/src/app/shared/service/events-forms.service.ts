import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class EventsFormsService {
  private apiUrl = 'http://localhost:8000/api/contact'; // Url de symfony

  constructor(private http: HttpClient) {}

  sendEventForm(data: any): Observable<any> {
    return this.http.post(this.apiUrl, data);
  }
}
