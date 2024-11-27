import {createSlice, PayloadAction} from '@reduxjs/toolkit'
import {SubjectData} from 'shared/types'

type SubjectEntitySlice = {
	subjects: SubjectData[],
	initialized: boolean,
	loading: boolean,
}

const emptyUser: SubjectEntitySlice = {
	subjects: [],
	initialized: false,
	loading: true,
}

const initialState: SubjectEntitySlice = emptyUser

const subjectEntitySlice = createSlice({
	name: 'subjectEntity',
	initialState,
	reducers: {
		setSubjects: (state: SubjectEntitySlice, action: PayloadAction<SubjectData[]>) => {
			state.subjects = action.payload
			state.initialized = true
			state.loading = false
		},
		setLoading: (state, action: PayloadAction<boolean>) => {
			state.loading = action.payload
		},
		setInitialized: (state, action: PayloadAction<boolean>) => {
			state.initialized = action.payload
		},
	},
})

export {
	subjectEntitySlice,
}