export interface ReportResponse {
  name: string
  num_employees: number
}

export interface ReportCardState {
  loading: boolean,
  reportItems: ReportResponse[]
}
