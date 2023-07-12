import{s as F,r,o as f,h as g,j as e,a as y,d as t,m as A,A as B,y as k,e as b,c as H,z as T,v as u,x as c,F as V}from"./app.ca5d1c04.js";const h={class:"grid grid-cols-3 gap-6"},x={class:"col-span-3 sm:col-span-1"},R={class:"col-span-3 sm:col-span-1"},U={__name:"Filter",emits:["hide"],setup($,{emit:p}){const _={search:"",startDate:"",endDate:""},a=F({..._});return(l,s)=>{const d=r("BaseInput"),o=r("DatePicker"),v=r("FilterForm");return f(),g(v,{"init-form":_,form:a,onHide:s[3]||(s[3]=i=>p("hide"))},{default:e(()=>[y("div",h,[y("div",x,[t(d,{type:"text",modelValue:a.search,"onUpdate:modelValue":s[0]||(s[0]=i=>a.search=i),name:"search",label:l.$trans("general.search")},null,8,["modelValue","label"])]),y("div",R,[t(o,{type:"text",start:a.startDate,"onUpdate:start":s[1]||(s[1]=i=>a.startDate=i),end:a.endDate,"onUpdate:end":s[2]||(s[2]=i=>a.endDate=i),name:"dateBetween",as:"range",label:l.$trans("general.date_between")},null,8,["start","end","label"])])])]),_:1},8,["form"])}}},j={name:"ActivityLogList"},N=Object.assign(j,{setup($){A();const p=B("emitter"),_="utility/activityLog/",a=k(!1),l=F({}),s=d=>{Object.assign(l,d)};return(d,o)=>{const v=r("PageHeaderAction"),i=r("PageHeader"),D=r("ParentTransition"),m=r("DataCell"),w=r("DataRow"),I=r("DataTable"),P=r("ListItem");return f(),g(P,{"init-url":_,onSetItems:s},{header:e(()=>[t(i,{title:d.$trans("utility.activity.log")},{default:e(()=>[t(v,{url:"utility/activity-logs/",name:"UtilityActivityLog",title:d.$trans("utility.activity.log"),actions:["filter"],"dropdown-actions":["print","pdf","excel"],onToggleFilter:o[0]||(o[0]=n=>a.value=!a.value)},null,8,["title"])]),_:1},8,["title"])]),filter:e(()=>[t(D,{appear:"",visibility:a.value},{default:e(()=>[t(U,{onRefresh:o[1]||(o[1]=n=>b(p).emit("listItems")),onHide:o[2]||(o[2]=n=>a.value=!1)})]),_:1},8,["visibility"])]),default:e(()=>[t(D,{appear:"",visibility:!0},{default:e(()=>[t(I,{header:l.headers,meta:l.meta,module:"utility.activity",onRefresh:o[3]||(o[3]=n=>b(p).emit("listItems"))},{default:e(()=>[(f(!0),H(V,null,T(l.data,n=>(f(),g(w,{key:n.uuid},{default:e(()=>[t(m,{name:"user"},{default:e(()=>[u(c(n.user?n.user.profile.name:"-"),1)]),_:2},1024),t(m,{name:"activity"},{default:e(()=>[u(c(n.activity),1)]),_:2},1024),t(m,{name:"ip"},{default:e(()=>[u(c(n.ip),1)]),_:2},1024),t(m,{name:"browser"},{default:e(()=>[u(c(n.browser),1)]),_:2},1024),t(m,{name:"os"},{default:e(()=>[u(c(n.os),1)]),_:2},1024),t(m,{name:"createdAt"},{default:e(()=>[u(c(n.createdAt),1)]),_:2},1024)]),_:2},1024))),128))]),_:1},8,["header","meta"])]),_:1})]),_:1})}}});export{N as default};
